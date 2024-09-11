import { useEffect } from "react";
import { useLoaderData, useNavigate } from "react-router-dom";
type User = {
    username: string;
}
export const Dashboard = () => {
    const navigate = useNavigate();
    const data = useLoaderData() as null | User;
    useEffect(() => { if (!data || data === null) navigate("/login"); }, [data, navigate]); if (!data || data === null) return null;
    return <div>
    </div>
}