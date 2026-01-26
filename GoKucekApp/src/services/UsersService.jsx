import api from "./api";
import { GetWithExpiry } from "../utils/SetWithExpiry";

const getTenantId = () => {
  const user = GetWithExpiry("user");
  return user?.tenant_id || null;
};

const UsersService = {
  getUsers: (params = {}) => {
    const tenantId = getTenantId();

    return api.get("/user", {
      params: {
        tenant_id: tenantId,
        ...params, // page, per_page, keyword, dll
      },
    });
  },

  createUser: (payload) => {
    const tenantId = getTenantId();
    const formData = new FormData();
    formData.append("full_name", payload.full_name);
    formData.append("email", payload.email);
    formData.append("username", payload.username);
    formData.append("phone", payload.phone);
    formData.append("role_id", payload.role_id);
    formData.append("is_active", payload.is_active);

    if (payload.password) {
      formData.append("password", payload.password);
    }

    if (tenantId) {
      formData.append("tenant_id", tenantId);
    }

    if (payload.avatar instanceof File) {
      formData.append("avatar", payload.avatar);
    }

    return api.post("/user", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  updateUser: (id, payload) => {
    const formData = new FormData();

    formData.append("_method", "PUT");

    Object.entries(payload).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        if (value instanceof File) {
          formData.append(key, value);
        } else if (typeof value === "object") {
          formData.append(key, JSON.stringify(value));
        } else {
          formData.append(key, value);
        }
      }
    });

    return api.post(`/user/${id}`, formData);
  },

  deleteUser: (id) => {
    const tenantId = getTenantId();
    return api.delete(`/user/${id}`, {
      params: { tenant_id: tenantId },
    });
  },

  getUserById: (id) => {
    const tenantId = getTenantId();
    return api.get(`/user/${id}`, {
      params: { tenant_id: tenantId },
    });
  },
};

export default UsersService;
