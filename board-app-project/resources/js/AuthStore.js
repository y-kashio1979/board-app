import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        name: "",
        isLoggedIn: false,
    }),

    actions: {
        login(user) {
            this.name = user.name;
            this.isLoggedIn = true;
        },

        logout() {
            this.name = "";
            this.isLoggedIn = false;
        },

        async fetchUser() {
            try {
                const response = await axios.get("api/auth/me");

                this.name = response.data.name;
                this.isLoggedIn = true;
            } catch {
                this.name = "";
                this.isLoggedIn = false;
            }
        }
    },
});