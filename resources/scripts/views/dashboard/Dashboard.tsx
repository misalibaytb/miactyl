import axios from "axios";
import { useEffect } from "react";
import { Link, useLoaderData, useNavigate } from "react-router-dom";
type User = {
    username: string;
}
export const Dashboard = () => {
    const navigate = useNavigate();
    const data = useLoaderData() as null | User;
    useEffect(() => { if (!data || data === null) navigate("/login"); }, [data, navigate]); if (!data || data === null) return null;
    return <div>
        <h1>Welcome, {data.username}!</h1>
        <button onClick={() => axios.get("/api/logout").then(() => navigate(window.location.pathname))}>Logout</button>
        <Link to="/dashboard/data">Dashboard</Link>
    </div>
}