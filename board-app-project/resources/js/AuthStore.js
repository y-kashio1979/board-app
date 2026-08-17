import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        name: "",
    }),

    getters: {
        isLoggedIn: (state) => !!state.name,
    },

    actions: {
        login(user) {
            this.name = user.name;
        },

        logout() {
            this.name = "";
        },

        async fetchUser() {
            try {
                const response = await axios.get("api/auth/me");

                this.name = response.data.name;
            } catch {
                this.name = "";
            }
        }
    },
});