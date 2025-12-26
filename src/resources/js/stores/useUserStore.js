import { defineStore } from "pinia";
import { ref } from "vue";
import usersRepository from "@/repositories/usersRepository.js";

export const useUserStore = defineStore("userStore", () => {
    const users = ref([]);

    const fetchUsers = () => {
        const users = usersRepository.getUsers();
        users.value = users;
    };

    return {
        users,
        fetchUsers,
    };
});
