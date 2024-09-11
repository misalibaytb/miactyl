import { faTachometer, faServer, faGear, faCoins, faReceipt, faChain, faUserAstronaut } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from 'react-router-dom';
import '@/assets/sidebar.css'
import { useEffect, useState } from "react";

export default function Sidebar() {
    const [sidebarExpanded, setSidebarExpanded] = useState(true)
    useEffect(() => {
        const sidebar = document.getElementById("sidebar")
        const links = document.querySelectorAll(".sidebar-link")
        links.forEach((link) => {
            link.addEventListener("click", () => {
                if (!sidebar) return console.error("Sidebar not found")
                sidebar.setAttribute("data-open", "true")
            })
        })
        if (!sidebar) return console.error("Sidebar not found")
        setInterval(() => {
            if (sidebar.getAttribute("data-open")) setSidebarExpanded(false)
            else setSidebarExpanded(true)
        }, 100)
    }, [sidebarExpanded])

    return (
        <>
            <div className="sidebar" id="sidebar" data-open="true">
                <div className="sidebar-header">
                    <span id="headerName">{sidebarExpanded ? "<?php echo env[\"APP_NAME\"]; ?>" : "<?php echo env[\"APP_NAME\"][0]; ?>"}</span>
                    <div className="sidebar-close" onClick={() => {
                        console.log("Close")
                        const sidebar = document.getElementById("sidebar")
                        if (!sidebar) return console.error("Sidebar not found")
                        sidebar.setAttribute("data-open", "true")
                    }}>X</div>
                </div>
                <ul className="sidebar-items">
                    <li className="sidebar-category"><span className="sidebar-link text-white"><h4></h4></span></li>
                    <Link className="sidebar-link" to={"/dashboard"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard" ? "active" : "")}><div className="menu-icon text-blue"><FontAwesomeIcon icon={faTachometer} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/servers"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/servers" ? "active" : "")}><div className="menu-icon text-purple"><FontAwesomeIcon icon={faServer} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/createServer"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/createServer" ? "active" : "")}><div className="menu-icon text-green"><FontAwesomeIcon icon={faServer} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/transactions"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/transactions" ? "active" : "")}><div className="menu-icon text-orange"><FontAwesomeIcon icon={faReceipt} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/settings"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/settings" ? "active" : "")}><div className="menu-icon text-blue"><FontAwesomeIcon icon={faGear} /></div><span className="menu-title"></span></li></Link>
                    <li className="sidebar-category"><span className="sidebar-link text-white"><h4></h4></span></li>
                    <Link className="sidebar-link" to={"/dashboard/leaderboard"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/leaderboard" ? "active" : "")}><div className="menu-icon text-blue"><FontAwesomeIcon icon={faUserAstronaut} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/gift"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/gift" ? "active" : "")}><div className="menu-icon text-purple"><FontAwesomeIcon icon={faCoins} /></div><span className="menu-title"></span></li></Link>
                    <Link className="sidebar-link" to={"/dashboard/earn"}><li className={"sidebar-item menu-items  " + (window.location.pathname === "/dashboard/earn" ? "active" : "")}><div className="menu-icon text-green"><FontAwesomeIcon icon={faCoins} /></div><span className="menu-title"></span></li></Link>
                    <li className="sidebar-category"><span className="sidebar-link text-white"><h4></h4></span></li>
                    <Link className="sidebar-link" to="/dashboard/authenticate" target="_blank"><li className={"sidebar-item menu-items  "}><div className="menu-icon text-orange"><FontAwesomeIcon icon={faChain} /></div><span className="menu-title"></span></li></Link>
                </ul>
            </div >
        </>
    )
}