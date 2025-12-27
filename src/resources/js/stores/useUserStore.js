import { defineStore } from "pinia";
import { ref } from "vue";
import usersRepository from "@/repositories/usersRepository.js";

export const useUserStore = defineStore("userStore", () => {
    const users = ref([]);

    // Add authenticated user which is picked from the repo, which gets the users file and sets the first one as the authenticated one for testing purposes.
    const authenticatedUser = ref(usersRepository.getUsers()[0]);
    
    const fetchUsers = () => {
        const users = usersRepository.getUsers();
        users.value = users;
    };

    return {
        users,
        authenticatedUser,
        fetchUsers,
    };
});
