import axios from "axios"
import { useEffect } from "react";
import { createBrowserRouter, Link, RouterProvider, useLoaderData, useNavigate } from "react-router-dom"
type User = {
    username: string;
}
const Home = () => {
    const navigate = useNavigate();
    const data = useLoaderData() as null | User;
    useEffect(() => { if (!data || data === null) navigate("/login"); }, [data, navigate]); if (!data || data === null) return null;
    return <div>
        <h1>Welcome, {data.username}!</h1>
        <button onClick={() => axios.get("/api/logout").then(() => navigate(window.location.pathname))}>Logout</button>
        <Link to="/dashboard/data">Dashboard</Link>
    </div>
}
const Login = () => {
    return <div className="center login">
        <div className="login-wrapper">
            <h3>Welcome to CakeNodes!</h3>
            <p>Free Minecraft server hosting!!</p>
            <p>Click below to get started!</p>
            <button onClick={() => axios.get("/api/login").then((res) => window.location.href = res.data)}>Login with Discord</button>
        </div>
    </div>
}
const LoadingComponent = () => {
    const loadingNames = ["loading... grabbing a coffee for you. be right back!", "loading... almost there, just untangling some wires.", "loading... adding the final sprinkles of magic.", "loading... feeding the hamsters powering this app.", "loading... still better than a dial-up connection!", "loading... because good things come to those who wait.", "loading... hold tight, we're tuning the engine.", "loading... practicing our ninja skills in the background.", "loading... counting the pixels, one by one.", "loading... your patience level is impressive!"]
    return (
        <div className="loading-container">
            <div className="loader"></div>
            {loadingNames[Math.floor(Math.random() * loadingNames.length)]}
        </div>
    )
}

const Oauth2 = () => {
    const navigate = useNavigate();
    useEffect(() => { navigate("/dashboard"); }, [navigate]);
    return <></>
}

// const Router = createBrowserRouter(createRoutesFromElements(<>
//     <Route path="/" element={<Home />} loader={() => axios.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)} />
//     <Route path="/callback" element={<Oauth2 />} loader={() => axios.post("/api/login", { code: new URLSearchParams(window.location.search).get("code") }, {
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         }
//     }).catch(() => null)} />
//     <Route path="/dashboard" loader={() => axios.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)}>
//         <Route path="/dashboard" element={<Home />} />
//     </Route>
//     <Route path="/login" element={<Login />} />
// </>
// ))
const Router = createBrowserRouter([
    {
        path: "/callback",
        element: <Oauth2 />,
        loader: () => axios.post("/api/login", { code: new URLSearchParams(window.location.search).get("code") }, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        }).catch(() => null)
    },
    {
        path: "/dashboard",
        element: <Home />,
        children: [
            {
                path: "/dashboard/data",
                element: <Home />,
                loader: () => axios.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
            }
        ],
        loader: () => axios.get("/api/user").then(res => res.data).then(data => data.user ?? null).catch(() => null)
    },
    {
        path: "/login",
        element: <Login />,
    }
])

export const PageRouter = () => {
    return (
        <RouterProvider
            router={Router}
            future={{
                v7_startTransition: true,
            }}
            fallbackElement={
                <LoadingComponent />
            }
        />
    )
}