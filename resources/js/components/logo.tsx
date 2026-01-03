import { cn } from '@/lib/utils';
import { ImgHTMLAttributes } from 'react';

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
            className={cn('block object-contain select-none', className)}
            {...props}
        />
    );
}
