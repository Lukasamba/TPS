import { createVuexModule } from "vuex-typed-modules";
import { GetMsalState, WatchMsalState } from "../../msal";

interface AuthModuleState {
  isReady: boolean;
  isLoggedIn: boolean;
  accessToken: null;
  idToken: null;
  email: null;
  displayName: null;
  onReady: (() => void)[];
}

const defaultState: AuthModuleState = {
  isReady: false,
  isLoggedIn: false,
  accessToken: null,
  idToken: null,
  email: null,
  displayName: null,
  onReady: [],
};

export const [authModule, useAuthModule] = createVuexModule({
  name: "authModule",
  state: defaultState,
  actions: {
    initialize({ commit }) {
      WatchMsalState(async () => {
        const state = GetMsalState();
        let isAdmin = false;
        commit("setState", { ...state, isAdmin });
      });
    },
    async waitTillReady({ commit, state }) {
      if (!state.isReady) {
        await new Promise((c) => {
          commit("addReadyCalback", c);
        });
      }
    },
  },
  mutations: {
    setState(state, msalState) {
      state.isLoggedIn = msalState.isLoggedIn;
      state.accessToken = msalState.accessToken;
      state.idToken = msalState.idToken;
      state.email = msalState.email;
      state.displayName = msalState.displayName;
      if (!state.isReady && state.idToken) {
        state.isReady = true;
        state.onReady.forEach((c) => c());
      }
    },
    addReadyCalback(state, callback: () => void) {
      state.onReady.push(callback);
    },
  },
});
