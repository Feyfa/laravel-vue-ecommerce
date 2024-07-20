import { createStore } from "vuex";
import axios from "@/axios";

export default createStore({
  state: {
    token: '',
    user: ''
  },

  getters: {
    token: state => state.token,
    user: state => state.user,
  },

  actions: {
    fetchTokenFromLocalStorage() {
      this.state.token = localStorage.getItem('token');
    },

    fetchUserFromLocalStorage() {
      this.state.user = JSON.parse(localStorage.getItem('user'));
    },

    logoutSubmit(context, data) {
      return new Promise((resolve, reject) => {
        axios.post('/logout')
             .then(response => {
              resolve(response);
             })
             .catch(error => {
              reject(error);
             })
      });
    },

    loginSubmit(context, data) {
      return new Promise((resolve, reject) => {
        axios.post('/login', {
          email: data.email,
          password: data.password,
        })
        .then(response => {
          resolve(response);
        })
        .catch(error => {
          reject(error);
        });
      });
    },

    registerSubmit(context, data) {
      return new Promise((resolve, reject) => {
        axios.post('/register', {
          name: data.name,
          email: data.email,
          password: data.password,
        })
        .then(response => {
          resolve(response);
        })
        .catch(error => {
          reject(error);
        });
      });
    }
  }
})