import Logo from '@/components/logo';
import { home } from '@/routes';
import { Link } from '@inertiajs/react';
import { useEffect, useState, type PropsWithChildren } from 'react';

interface AuthLayoutProps {
    title?: string;
    description?: string;
}

const FEATURES = [
    {
        title: 'Instant Link Shortening',
        description:
            'Convert long URLs into clean, shareable links in seconds.',
        icon: '📎',
    },
    {
        title: 'Real-Time Analytics',
        description:
            'Track clicks, geographic data, and device information instantly.',
        icon: '📊',
    },
    {
        title: 'Lightning-Fast Redirects',
        description:
            'High-speed link redirection with global CDN distribution.',
        icon: '⚡',
    },
    {
        title: 'Enterprise Security',
        description:
            'SSL encryption and advanced fraud detection for peace of mind.',
        icon: '🛡️',
    },
];

export default function AuthSplitLayout({
    children,
    title,
    description,
}: PropsWithChildren<AuthLayoutProps>) {
    const [index, setIndex] = useState(0);
    useEffect(() => {
        const interval = setInterval(() => {
            setIndex((i) => (i + 1) % FEATURES.length);
        }, 5000);

        return () => clearInterval(interval);
    }, []);

    const feature = FEATURES[index];

    return (
        <div className="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            {/* LEFT PANEL */}
            <div className="relative hidden h-full flex-col justify-between overflow-hidden bg-gradient-to-br from-primary to-primary-dark p-10 text-white lg:flex">
                <Link
                    href={home()}
                    className="relative z-10 flex items-center text-lg font-medium"
                >
                    <Logo className="mr-2 h-10 fill-current text-white" />
                </Link>

                {/* Center Feature */}
                <div className="relative z-10 flex flex-col items-center text-center">
                    <div className="mb-8 flex h-24 w-24 items-center justify-center rounded-2xl bg-white/10 text-5xl backdrop-blur">
                        {feature.icon}
                    </div>

                    <h3 className="mb-4 text-4xl font-bold">{feature.title}</h3>

                    <p className="mb-10 max-w-md text-white/90">
                        {feature.description}
                    </p>

                    {/* Dots */}
                    <div className="flex gap-3">
                        {FEATURES.map((_, i) => (
                            <button
                                key={i}
                                onClick={() => setIndex(i)}
                                className={`h-2 rounded-full transition-all ${
                                    i === index
                                        ? 'w-8 bg-white'
                                        : 'w-2 bg-white/30'
                                }`}
                            />
                        ))}
                    </div>
                </div>

                {/* Footer */}
                <div className="relative z-10 border-t border-white/20 pt-8">
                    <p className="mb-4 text-sm text-white/90">
                        ✨ Join thousands of marketers and developers using SHWT
                    </p>

                    <div className="flex items-center gap-3">
                        <div className="flex -space-x-2">
                            {['A', 'B', 'C', 'D'].map((l, i) => (
                                <div
                                    key={l}
                                    className={`flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold text-white ring-2 ring-white ${
                                        i % 2 === 0
                                            ? 'bg-accent'
                                            : 'bg-accent-light'
                                    }`}
                                >
                                    {l}
                                </div>
                            ))}
                        </div>

                        <div>
                            <p className="text-sm font-semibold">
                                2,847+ Active Users
                            </p>
                            <p className="text-xs text-white/80">
                                Trusted by teams worldwide
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* RIGHT PANEL */}
            <div className="w-full lg:p-8">
                <div className="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <Link
                        href={home()}
                        className="relative z-20 flex items-center justify-center"
                    >
                        <Logo className="mr-2 size-3/5 h-auto fill-current text-white" />
                    </Link>

                    <div className="flex flex-col items-start gap-2 text-left sm:items-center sm:text-center">
                        <h1 className="text-xl font-medium">{title}</h1>
                        <p className="text-sm text-balance text-muted-foreground">
                            {description}
                        </p>
                    </div>

                    {children}
                </div>
            </div>
        </div>
    );
}
