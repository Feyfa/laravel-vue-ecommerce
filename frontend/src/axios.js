import axios from "axios";

const instance = axios.create({
  baseURL: 'http://ecommerce.backend.com/api',
  timeout: 30000
});

// // Buat interceptor untuk menetapkan header Authorization
// instance.interceptors.request.use(
//   config => {
//     // Ambil token dari localStorage atau tempat penyimpanan lainnya
//     const token = localStorage.getItem('token');

//     // Jika token ditemukan, tambahkan ke header Authorization
//     if(token)
//       config.headers.Authorization = `Bearer ${token}`;

//     return config;
//   },
//   error => {
//     return Promise.reject(error);
//   }
// );

export default instance;