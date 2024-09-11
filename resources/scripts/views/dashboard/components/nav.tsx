import {
    faBars,
    faCaretDown
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import '@/assets/nav.css'
import { useLoaderData, useNavigate } from "react-router-dom";
import axios from "axios";
const system = {
    language: {
        'Coins': 'Coins',
        'Logout': 'Logout'
    }
}
type User = {
    id: number;
    username: string;
    coins: number;
    avatar: string;
}
export default function Navbar() {
    const user = useLoaderData() as User;
    const navigate = useNavigate()
    const toggleSidebar = () => {
        const sidebar = document.getElementById("sidebar")
        if (!sidebar) return console.error("Sidebar not found")
        if (sidebar.getAttribute("data-open")) {
            sidebar.removeAttribute("data-open")
        } else {
            sidebar.setAttribute("data-open", "true")
        }
    }
    return (
        <>
            <div className="nav">
                <div className="sidebar-switch" onClick={toggleSidebar}>
                    <FontAwesomeIcon icon={faBars} />
                </div>
                <div className="sidebar-avatar" onClick={() => {
                    const dropdown = document.querySelector(".dropdown")
                    if (!dropdown) return console.error("Dropdown not found")
                    if (dropdown.getAttribute("data-active") === "true") {
                        dropdown.removeAttribute("data-active")
                        document.removeEventListener('click', () => { })
                    } else {
                        dropdown.setAttribute("data-active", "true")
                        document.addEventListener('click', (e) => {
                            if (!e.target) return;
                            const target = e.target as Element;
                            if (target.closest('.sidebar-avatar')) return;
                            dropdown.removeAttribute("data-active")
                        })
                    }
                }}>
                    <img src={user.avatar} className="avatar" alt="avatar" />
                    <span>{user.username}</span>
                    <div className="dropdown">
                        <span className="caret"><FontAwesomeIcon icon={faCaretDown} /></span>
                        <div className="dropdown-content">
                            <a>{system.language['Coins']}: {user.coins}</a>
                            <a onClick={() => {
                                axios.get("/api/logout").then(() => navigate("/login"))
                            }}>{system.language['Logout']}</a>
                        </div>
                    </div>
                </div>
            </div >
        </>
    )
}