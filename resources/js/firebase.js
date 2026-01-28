import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDlPEIuw2hv20hPt8it4yxun990vlxOW4E",
  authDomain: "autocar-9a1a7.firebaseapp.com",
  databaseURL: "https://autocar-9a1a7-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "autocar-9a1a7",
  storageBucket: "autocar-9a1a7.firebasestorage.app",
  messagingSenderId: "1053506486964",
  appId: "1:1053506486964:web:024511947dd0d15e18cde1",
  measurementId: "G-TT2NKGVF2H"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

export { app, analytics };
