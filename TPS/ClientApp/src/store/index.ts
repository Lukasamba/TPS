import { createLogger, createStore } from "vuex";
import { Database } from "vuex-typed-modules";
import { authModule } from "./modules/auth";

const debug = process.env.NODE_ENV !== "production";

const database = new Database({ logger: debug });

const typedModules = [authModule];
const plugins = [database.deploy(typedModules)];

export default createStore({
  strict: debug,
  plugins: debug ? [createLogger(), ...plugins] : plugins,
});
