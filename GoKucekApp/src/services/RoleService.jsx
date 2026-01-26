import api from "./api";

const RoleService = {
  getRoles() {
    return api.get("/roles");
  },
};

export default RoleService;
