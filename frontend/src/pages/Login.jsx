import { useState } from "react";
import api from "..api/axios";


export default function Login(){
    const[email, setEmail] = useState("");
    const[password, setPassword] = useState("");


const handleLogin = async (e) => {
    e.preventDefault();

    try {
        const response = await api.post("/login",{
            email,
            password,

        });

        const token = response.data.token;

        //storing the token
        localStorage.setItem("token",token);

        console.log("Logged in",token);
    } catch (error){
        console.log(error.response?.data);
    }
};


return (
    <form onSubmit={handleLogin}>
        <h2>Login</h2>

        <input 
        type="email"
        placeholder="email"
        onChange={(e) => setEmail(e.target.value)} 
        
        />

        <input
        type="password"
        placeholder="password"
        onChange={(e) => setPassword(e.target.value)}
        />

        <button type="submit">Submit</button>

    </form>
)

}

