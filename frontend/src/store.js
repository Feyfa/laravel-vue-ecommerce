import { createStore } from "vuex";
import axios from "@/axios";

export default createStore({
  actions: {
    jidanTest(context, data) {
      return new Promise((resolve, reject) => {
        axios.get('/jidan/test', {
          params: {
            nama: data.nama,
            umur: data.umur,
          }
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