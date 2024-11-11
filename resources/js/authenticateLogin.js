import {getAuth, signInWithEmailAndPassword} from "firebase/auth";
                            //the firebase documentation (specifically auth_signin_password.js) helped with the creation of this authentication: https://firebase.google.com/docs/auth/web/start
const auth = getAuth();

signInWithEmailAndPassword(auth, email, password)
.then((userCredential => {
    //Signed in
    const user = userCredential.user;
}))
.catch((error) => {
    const errorCode = error.code;
    const errorMessage = error.message;
    console.log("Error code:", errorCode, "Error Message:", errorMessage);
});

const getFirebaseToken = async () => {
    const user = auth.currentUser;
    if (user) {
        return await user.getIdToken();
    }
    return null;
};

console.log(getFirebaseToken);