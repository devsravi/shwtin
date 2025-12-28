import { ImgHTMLAttributes } from 'react';
import { cn } from '@/lib/utils';

interface LogoProps extends ImgHTMLAttributes<HTMLImageElement> {
    src?: string;
}

export default function Logo({
    src = '/shwtinnew.png',
    alt = 'App Logo',
    className,
    ...props
}: LogoProps) {
    return (
        <img
            src={src}
            alt={alt}
            draggable={false}
            className={cn(
                'block select-none object-contain',
                className
            )}
            {...props}
        />
    );
}
