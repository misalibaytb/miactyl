import Navbar from "@/views/skeleton/components/nav"
import Sidebar from "@/views/skeleton/components/sidebar"
import { faAdd } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
export const SkeletonDashboardLayout = () => {
    return <>
        <div className="routesWrapper">
            <Sidebar />
            <div className="routes-container">
                <Navbar />
                <div className="routes-center">
                    <div className="routes">
                        <div className="servers__row">
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"></a></p>
                                </div>
                            </div>
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"><FontAwesomeIcon icon={faAdd} /></a></p>
                                </div>
                            </div>
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"><FontAwesomeIcon icon={faAdd} /></a></p>
                                </div>
                            </div>
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"></a></p>
                                </div>
                            </div>
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"></a></p>
                                </div>
                            </div>
                            <div className="servers__row__item">
                                <h2 className="servers__row__item-title skeleton-text"></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <h2 className="skeleton-text-small" style={{ width: Math.floor(Math.random() * 100) + '%' }}></h2>
                                <div className="servers__row__item-buttons" style={{ justifyContent: 'center' }}>
                                    <p className="servers__row__item-button skeleton-btn" data-secondary><a href="#"><FontAwesomeIcon icon={faAdd} /></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </>
}
