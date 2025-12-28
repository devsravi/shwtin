import * as React from 'react';
import { cn } from '@/lib/utils';

function Input({
    className,
    type = 'text',
    ...props
}: React.ComponentProps<'input'>) {
    return (
        <input
            type={type}
            data-slot="input"
            className={cn(
                /* Base layout */
                'block w-full h-10 rounded-lg px-3 text-sm appearance-none outline-none',

                /* Colors */
                'bg-white text-zinc-700 placeholder-zinc-400',
                'dark:bg-white/10 dark:text-zinc-300 dark:placeholder-zinc-400',

                /* Borders */
                'border border-zinc-200',
                'dark:border-white/10',

                /* Focus */
                'focus-visible:border-ring',
                'focus-visible:ring-2 focus-visible:ring-ring/40',

                /* Selection */
                'selection:bg-primary selection:text-primary-foreground',

                /* Disabled */
                'disabled:cursor-not-allowed disabled:opacity-60',
                'disabled:bg-zinc-50 dark:disabled:bg-white/[7%]',
                'disabled:text-zinc-500 dark:disabled:text-zinc-400',
                'disabled:border-zinc-200 dark:disabled:border-white/5',

                /* Invalid */
                'aria-invalid:border-destructive',
                'aria-invalid:ring-2 aria-invalid:ring-destructive/30',

                className
            )}
            {...props}
        />
    );
}

export { Input };
