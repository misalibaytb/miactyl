import { SystemContext } from "@/state/system";

export default function Footer() {
    const system = SystemContext.useStoreState((state) => state.system);
    return (
        <footer className="footer">
            <h1>{system.footer} | Powered by Miactyl</h1>
        </footer>
    )
}