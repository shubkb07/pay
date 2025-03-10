/**
 * Debug utilities for animation troubleshooting
 * Include this file temporarily if animations aren't working
 */

document.addEventListener('DOMContentLoaded', () => {
    // Add debug controls to the page
    const debugPanel = document.createElement('div');
    debugPanel.style.position = 'fixed';
    debugPanel.style.bottom = '10px';
    debugPanel.style.right = '10px';
    debugPanel.style.zIndex = '9999';
    debugPanel.style.background = 'rgba(0,0,0,0.7)';
    debugPanel.style.padding = '10px';
    debugPanel.style.borderRadius = '5px';
    debugPanel.style.color = 'white';
    debugPanel.innerHTML = `
        <div style="margin-bottom:5px">Animation Debug</div>
        <button id="debug-show-all" style="background:#3b82f6;color:white;padding:5px;margin-right:5px;border-radius:3px">Show All</button>
        <button id="debug-highlight" style="background:#ef4444;color:white;padding:5px;margin-right:5px;border-radius:3px">Highlight Hidden</button>
    `;
    document.body.appendChild(debugPanel);
    
    // Show all animations 
    document.getElementById('debug-show-all').addEventListener('click', () => {
        document.querySelectorAll('.appear-once').forEach(el => {
            el.classList.add('animate-fade-in');
        });
    });
    
    // Highlight elements that should be animated
    document.getElementById('debug-highlight').addEventListener('click', () => {
        document.querySelectorAll('.appear-once:not(.animate-fade-in)').forEach(el => {
            el.classList.toggle('debug-show');
        });
    });
    
    // Show counts of animated vs non-animated elements
    console.log('Animation debug info:');
    const total = document.querySelectorAll('.appear-once').length;
    const visible = document.querySelectorAll('.appear-once.animate-fade-in').length;
    console.log(`Total animation elements: ${total}`);
    console.log(`Currently visible: ${visible}`);
    console.log(`Hidden: ${total - visible}`);
});
