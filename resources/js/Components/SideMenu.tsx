import { Link } from '@inertiajs/react';
import { MenuItem } from '@/types/MenuItem';

interface SideMenuProps {
    menuItems: MenuItem[];
}

const SideMenu: React.FC<SideMenuProps> = ({ menuItems }) => {
    return (
        <nav className="side-nav">
            <Link href="" className="intro-x flex items-center pl-5 pt-4 mt-3">
                <img alt="Midone - HTML Admin Template" className="w-6" src="dist/images/logo.svg" />
                <span className="hidden xl:block text-white text-lg ml-3"> Tinker </span>
            </Link>
            <div className="side-nav__divider my-6"></div>
            <ul>
                {menuItems.map((menuItem, index) => (
                    <li key={index}>
                        {menuItem.route && menuItem.type === "link" ? (
                            <Link href={menuItem.route} className={`side-menu ${menuItem.active ? 'side-menu--active' : ''}`}>
                                <div className="side-menu__icon">
                                    {menuItem.icon && (
                                        menuItem.icon.type === 'feather' ? (
                                            <i className="align-middle" data-feather={menuItem.icon.content}></i>
                                        ) : (
                                            <i data-lucide={menuItem.icon.content}></i>
                                        )
                                    )}
                                </div>
                                <div className="side-menu__title"> {menuItem.content} </div>
                            </Link>
                        ) : menuItem.type === 'divider' ? (
                            <div className="side-nav__divider my-6"></div>
                        ) : menuItem.sublinks ? (
                            <>
                                {menuItem.route && (
                                    <Link href={menuItem.route} className={`side-menu ${menuItem.active ? 'side-menu--active' : ''}`}>
                                        <div className="side-menu__icon">
                                            {menuItem.icon && (
                                                menuItem.icon.type === 'feather' ? (
                                                    <i className="align-middle" data-feather={menuItem.icon.content}></i>
                                                ) : (
                                                    <i data-lucide={menuItem.icon.content}></i>
                                                )
                                            )}
                                        </div>
                                        <div className="side-menu__title"> {menuItem.content} </div>
                                    </Link>
                                )}
                                <ul className="side-menu__sub-open">
                                    {menuItem.sublinks.map((subMenuItem, subIndex) => (
                                        <li key={subIndex}>
                                            {subMenuItem.route && (
                                                <Link href={subMenuItem.route} className="side-menu">
                                                    <div className="side-menu__icon">
                                                        <i data-lucide="corner-down-right"></i>
                                                    </div>
                                                    <div className="side-menu__title"> {subMenuItem.content} </div>
                                                </Link>
                                            )}
                                        </li>
                                    ))}
                                </ul>
                            </>
                        ) : null}
                    </li>
                ))}
            </ul>
        </nav>
    );
}

export default SideMenu;
