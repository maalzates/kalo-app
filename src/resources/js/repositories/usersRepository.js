import { users } from '@/data/Users';

const usersRepository = {
    getUsers() {
        return users;
    },
};

export default usersRepository;
