import * as msal from "@azure/msal-browser";

const ClientIdCookieName = "aaaa";

const RequestedScopes = [
  "openid",
  "email",
  "profile",
  "Calendars.ReadWrite",
  "Calendars.ReadWrite.Shared",
];

type StateChangeCallback = () => void;

interface MsalStateObject {
  msal?: msal.PublicClientApplication | null;
  clientId: string | null;
  stateChangeCallbacks: StateChangeCallback[];
  isLoggedIn: boolean;
  accessToken: string | null;
  idToken: string | null;
  email: string | null;
  displayName: string | null;
  msalRefreshTimer: ReturnType<typeof setInterval> | null;
  fullState?: msal.AuthenticationResult | null | void;
}

const msalState: MsalStateObject = {
  msal: null,
  clientId: null, // 5931fda0-e9e0-4754-80c2-18bcb9d9561a
  stateChangeCallbacks: [],

  isLoggedIn: false,
  accessToken: null,
  idToken: null,
  email: null,
  displayName: null,

  msalRefreshTimer: null,
  fullState: null,
};

async function initializeMSAL() {
  if (msalState.msal != null) {
    throw new Error("MSAL was attempted to initialize second time");
  }
  await __loadAuthParameters();
  const msalConfig: msal.Configuration = {
    auth: {
      clientId: msalState.clientId as string,
    },
  };
  msalState.msalRefreshTimer = setInterval(__refreshToken, 10 * 60 * 1000); // 10 mins

  msalState.msal = new msal.PublicClientApplication(msalConfig);

  msalState.msal.handleRedirectPromise().then(__handleResponse);

  (<any>window).msalState = msalState;
}

export function WatchMsalState(callback: StateChangeCallback) {
  msalState.stateChangeCallbacks.push(callback);
  callback();
}

export function GetMsalState() {
  return {
    accessToken: msalState.accessToken,
    idToken: msalState.idToken,
    isLoggedIn: msalState.isLoggedIn,
    email: msalState.email,
    displayName: msalState.displayName,
  };
}

export function LoginMsal() {
  msalState.msal?.loginRedirect({
    scopes: RequestedScopes,
  });
}

export function LogoutMsal() {
  sessionStorage.clear();
  window.location.reload();
}

async function __refreshToken() {
  if (!msalState.isLoggedIn) return;
  msalState.fullState = await msalState.msal
    ?.acquireTokenSilent({ scopes: RequestedScopes })
    .catch((error) => {
      if (error instanceof msal.InteractionRequiredAuthError) {
        // fallback to interaction when silent call fails
        return msalState.msal?.acquireTokenRedirect({
          scopes: RequestedScopes,
        });
      }
    });
  __responseObjectToMsalState();
  __stateChanged();
}

async function __handleResponse(response: msal.AuthenticationResult | null) {
  if (response !== null) {
    if (__isAccountAceptable(response.account!)) {
      msalState.msal?.setActiveAccount(response.account);
      msalState.fullState = response;

      __responseObjectToMsalState();
    }
  } else {
    msalState.msal
      ?.getAllAccounts()
      .filter(__isAccountAceptable)
      .forEach((account) => {
        msalState.msal?.setActiveAccount(account);
      });
    const account = msalState.msal?.getActiveAccount();
    if (account != null) {
      msalState.fullState = await msalState.msal
        ?.acquireTokenSilent({ scopes: RequestedScopes })
        .catch((error) => {
          if (error instanceof msal.InteractionRequiredAuthError) {
            // fallback to interaction when silent call fails
            return msalState.msal?.acquireTokenRedirect({
              scopes: RequestedScopes,
            });
          }
        });
      __responseObjectToMsalState();
    }
  }
  __stateChanged();
}

function __responseObjectToMsalState() {
  if (msalState.fullState) {
    msalState.isLoggedIn = true;
    msalState.accessToken = msalState.fullState.accessToken;
    msalState.idToken = msalState.fullState.idToken;
    msalState.email = (msalState.fullState.idTokenClaims as any).email;
    msalState.displayName = (msalState.fullState.idTokenClaims as any).name;
  } else {
    msalState.isLoggedIn = false;
  }
}

function __isAccountAceptable(account: msal.AccountInfo) {
  if (!account.tenantId) return false;
  return true;
}

function __stateChanged() {
  msalState.stateChangeCallbacks.forEach((cb) => cb());
}

async function __loadAuthParameters() {
  await __loadAuthParametersLocalStorage();
}

async function __loadAuthParametersLocalStorage() {
  const clientId = localStorage.getItem(ClientIdCookieName);
  if (clientId == null) {
    await __fetchAuthParameters();
    localStorage.setItem(ClientIdCookieName, msalState.clientId as string);
  } else {
    msalState.clientId = clientId;
  }
}

async function __fetchAuthParameters() {
  var response = await fetch("/api/AuthMetadata");
  msalState.clientId = (await response.json()).clientId;
}

initializeMSAL();
