// src/components/Nanobar.js
import React from 'react';
import '@/assets/nanobar.css';

class Nanobar {
    static setProgress(progress: number) {
        const nanobar = document.querySelector('.nanobar-progress');
        const container = document.querySelector('.nanobar-container');

        if (nanobar && container) {
            if (progress === 100) {
                // @ts-expect-error This is fine
                nanobar.style.width = `${progress}%`;
                setTimeout(() => {
                    container.classList.add('fade-out');
                    setTimeout(() => {
                        // @ts-expect-error This is fine
                        nanobar.style.width = '0%';
                        setTimeout(() => {
                            container.classList.remove('fade-out');
                        }, 300); // match with fade-out duration
                    }, 300); // match with nanobar animation duration
                }, 300);
            } else {
                // @ts-expect-error This is fine
                nanobar.style.width = `${progress}%`;
                container.classList.remove('fade-out');
            }
        }
    }
}

const NanobarComponent = () => {
    return (
        <div className="nanobar-container">
            <div className="nanobar-progress" />
        </div>
    );
};

export { Nanobar };
export default NanobarComponent;
