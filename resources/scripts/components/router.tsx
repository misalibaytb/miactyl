import { Suspense, useEffect } from "react";
import { createBrowserRouter, Link, RouterProvider, useLoaderData, useNavigate } from "react-router-dom"
import { Oauth2 } from "../views/public/Oauth2";
import { Dashboard } from "../views/dashboard/Dashboard";
import { Login } from "../views/public/Login";
import { LoadingComponent } from "./LoadingComponent";
import { DashboardLayout } from "./DashboardLayout";
import { SkeletonDashboardLayout } from "@/views/skeleton/DashboardLayout";
import { AxiosWrapper } from "./AxiosWrapper";
export type User = {
    username: string;
}

const Home = () => {
    const navigate = useNavigate();
    const data = useLoaderData() as null | User;
    useEffect(() => { if (!data || data === null) navigate("/login"); else navigate("/dashboard"); }, [data, navigate]);
    return null

}

const Router = createBrowserRouter([
    {
        path: "*",
        element: <Home />,
        loader: () => AxiosWrapper.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
    },
    {
        path: "/callback",
        element: <Oauth2 />,
        loader: () => AxiosWrapper.post("/api/login", { code: new URLSearchParams(window.location.search).get("code") }, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        }).catch(() => null)
    },
    {
        path: "/dashboard",
        children: [
            {
                path: "/dashboard/",
                element: <DashboardLayout><Dashboard /></DashboardLayout>,
                loader: () => AxiosWrapper.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
            },
            {
                path: "/dashboard/servers",
                element: <DashboardLayout><h1>Servers Baby</h1><Dashboard /></DashboardLayout>,
                loader: () => AxiosWrapper.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
            },
            {
                path: "/dashboard/*",
                element: <DashboardLayout><div className="not-found">
                    <h1>Ooops! 404</h1>
                    <h2>Page not found</h2>
                    <Link to="/dashboard" className="btn btn-primary">Go back in time</Link>
                </div></DashboardLayout>,
                loader: () => AxiosWrapper.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
            }
        ]
    },
    {
        path: "/login",
        element: <Login />,
        loader: () => AxiosWrapper.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
    }
])

export const PageRouter = () => {
    return (
        <>
            <Suspense fallback={<LoadingComponent />}>
                <RouterProvider
                    router={Router}
                    future={{
                        v7_startTransition: true,
                    }}
                    fallbackElement={
                        <SkeletonDashboardLayout />
                    }
                />
            </Suspense>
        </>
    )
}