import { useEffect } from "react";
import { useLoaderData, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";

export const Oauth2 = () => {
    const { data } = useLoaderData() as { data: { success?: boolean, error?: string } };
    const navigate = useNavigate();
    useEffect(() => {
        navigate("/dashboard");
        console.log(data)
        if (!data.success) toast.error(data.error ?? "Login with Discord failed! Please try again. If the issue persists, please contact support.")
        else toast.success("Login successful! Welcome to <?php echo env['APP_NAME'] ?>!")
    }, [data, navigate]);
    return <></>
}