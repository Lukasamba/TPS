import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import * as path from "path";
import { readFileSync } from "fs";

const baseFolder =
  process.env.APPDATA !== undefined && process.env.APPDATA !== ""
    ? `${process.env.APPDATA}/ASP.NET/https`
    : `${process.env.HOME}/.aspnet/https`;

const certificateArg = process.argv
  .map((arg) => arg.match(/--name=(?<value>.+)/i))
  .filter(Boolean)[0];
const certificateName = certificateArg
  ? certificateArg.groups.value
  : process.env.npm_package_name;

const certFilePath = path.join(baseFolder, `${certificateName}.pem`);
const keyFilePath = path.join(baseFolder, `${certificateName}.key`);

const target = process.env.ASPNETCORE_HTTPS_PORT
  ? `https://localhost:${process.env.ASPNETCORE_HTTPS_PORT}`
  : process.env.ASPNETCORE_URLS
  ? process.env.ASPNETCORE_URLS.split(";")[0]
  : "http://localhost:20938";

const apiProxy = {
  target,
  secure: false,
};

// https://vitejs.dev/config/
export default defineConfig(({}) => ({
  plugins: [vue()],
  server: {
    port: 44430,
    https: {
      key: readFileSync(keyFilePath),
      cert: readFileSync(certFilePath),
    },
    proxy: {
      //
      "/api": apiProxy,
      "/weatherforecast": apiProxy,
    },
  },
}));
