import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/company-register.css",
                "resources/css/company-login.css",
                "resources/css/company-dashboard.css",
                "resources/css/user-login.css",
                "resources/js/app.js",
                "resources/js/company-register.js",
                "resources/js/company-login.js",
                "resources/js/company-dashboard.js",
                "resources/js/user-register.js",
                "resources/js/user-login.js",
            ],
            refresh: true,
        }),
    ],
});
