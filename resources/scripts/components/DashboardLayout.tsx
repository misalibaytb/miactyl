import Navbar from "@/views/dashboard/components/nav"
import Sidebar from "@/views/dashboard/components/sidebar"
import { useEffect } from "react";
import { useLoaderData, useNavigate } from "react-router-dom";
type User = {
    id: number;
    username: string;
    coins: number;
}
export const DashboardLayout = ({ children }: { children: React.ReactNode }) => {
    const data = useLoaderData() as null | User;
    const navigate = useNavigate();
    useEffect(() => { if (!data || data === null) navigate("/login"); }, [data, navigate]); if (!data || data === null) return null;
    return <>
        <div className="routesWrapper">
            <Sidebar />
            <div className="routes-container">
                <Navbar />
                <div className="routes-center">
                    <div className="routes">
                        {children}
                    </div>
                </div>
            </div>
        </div>
    </>
}
