import * as React from 'react';
import * as SeparatorPrimitive from '@radix-ui/react-separator';

import { cn } from '@/lib/utils';

interface SeparatorProps
    extends React.ComponentProps<typeof SeparatorPrimitive.Root> {
    children?: React.ReactNode;
}

function Separator({
    className,
    orientation = 'horizontal',
    decorative = true,
    children,
    ...props
}: SeparatorProps) {
    // 🔹 Text separator
    if (children && orientation === 'horizontal') {
        return (
            <div className={cn('relative flex items-center', className)}>
                <span className="w-full border-t border-border" />
                <span
                    className="
                        absolute left-1/2 -translate-x-1/2
                        bg-background px-2
                        text-xs text-muted-foreground
                    "
                >
                    {children}
                </span>
            </div>
        );
    }

    // 🔹 Default separator
    return (
        <SeparatorPrimitive.Root
            data-slot="separator-root"
            decorative={decorative}
            orientation={orientation}
            className={cn(
                'bg-border shrink-0',
                'data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full',
                'data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px',
                className
            )}
            {...props}
        />
    );
}

export { Separator };
