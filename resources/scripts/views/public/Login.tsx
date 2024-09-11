import { User } from "@/components/router";
import axios from "axios"
import { useEffect } from "react";
import { useLoaderData, useNavigate } from "react-router-dom";

export const Login = () => {
    const navigate = useNavigate();
    const data = useLoaderData() as null | User;
    useEffect(() => { if (data && data.username !== null) navigate("/dashboard"); }, [data, navigate]);
    return <div className="center login">
        <div className="login-wrapper">
            <h3>Welcome to {"<?php echo env[\"APP_NAME\"]; ?>"}</h3>
            <p>Free Minecraft server hosting!!</p>
            <p>Click below to get started!</p>
            <button onClick={() => axios.get("/api/login").then((res) => window.location.href = res.data)}>Login with Discord</button>
        </div>
    </div>
}