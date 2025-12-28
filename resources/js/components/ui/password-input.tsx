import * as React from 'react';
import { cn } from '@/lib/utils';
import { Eye, EyeOff } from 'lucide-react';

type PasswordInputProps = React.ComponentProps<'input'>;

function PasswordInput({
    className,
    ...props
}: PasswordInputProps) {
    const [visible, setVisible] = React.useState(false);

    return (
        <div className="relative">
            <input
                type={visible ? 'text' : 'password'}
                data-slot="password-input"
                className={cn(
                    /* Base layout (same as Input) */
                    'block w-full h-10 rounded-lg px-3 pr-10 text-sm appearance-none outline-none',

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

            {/* Eye Toggle */}
            <button
                type="button"
                tabIndex={-1}
                onClick={() => setVisible((v) => !v)}
                aria-label={visible ? 'Hide password' : 'Show password'}
                className="
                    border-1
                    absolute right-0 top-0
                    flex h-full w-12 items-center justify-center
                    rounded-r-lg
                    text-muted-foreground
                    hover:text-foreground
                    focus:outline-none
                    cursor-pointer
                "
            >
                {visible ? (
                    <EyeOff className="h-4 w-4" />
                ) : (
                    <Eye className="h-4 w-4" />
                )}
            </button>
        </div>
    );
}

export { PasswordInput };
