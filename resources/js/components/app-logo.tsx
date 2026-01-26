import Logo from './logo';
export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-28 items-center justify-center rounded-md">
                <Logo className="fill-current text-white dark:text-black" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold"></span>
            </div>
        </>
    );
}
