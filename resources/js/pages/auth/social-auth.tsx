import { Button } from '@/components/ui/button';
import { GithubIcon, FacebookIcon } from 'lucide-react';
import GoogleIcon from '@/components/icons/google-icon';

/* -------------------------------------------------------
| Social Providers Config (Single Source of Truth)
|-------------------------------------------------------- */
type SocialProvider = {
    key: 'github' | 'google' | 'facebook';
    label: string;
    onClick?: () => void;
    icon: React.ComponentType<{ className?: string }>;
};

interface SocialAuthIconsProps {
    onGithub?: () => void;
    onGoogle?: () => void;
    onFacebook?: () => void;
}

export default function SocialAuthIcons({
    onGithub,
    onGoogle,
    onFacebook,
}: SocialAuthIconsProps) {
    const providers: SocialProvider[] = [
        {
            key: 'github',
            label: 'GitHub',
            onClick: onGithub,
            icon: GithubIcon,
        },
        {
            key: 'google',
            label: 'Google',
            onClick: onGoogle,
            icon: GoogleIcon,
        },
        {
            key: 'facebook',
            label: 'Facebook',
            onClick: onFacebook,
            icon: Facebook,
        },
    ].filter((provider) => provider.onClick); // only render enabled providers

    return (
        <div className="flex justify-center gap-3">
            {providers.map(({ key, label, onClick, icon: Icon }) => (
                <Button
                    key={key}
                    type="button"
                    variant="outline"
                    onClick={onClick}
                    title={`Continue with ${label}`}
                    className="
                        h-11 w-11 rounded-full
                        border-input
                        bg-background
                        text-foreground
                        hover:bg-muted
                        focus-visible:ring-primary/30
                    "
                >
                    <Icon className="h-5 w-5" />
                </Button>
            ))}
        </div>
    );
}
