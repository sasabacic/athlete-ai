import axios from "axios";

//we are saying create the version of axios that uses this base URL
const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
});

export default api;