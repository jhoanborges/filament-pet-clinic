import React, { useEffect } from 'react';

function PosComponent({ wire, ...props }) {
    const message = props.mingleData.message;

    useEffect(() => {
        console.log('PosComponent has been mounted');

        return () => {
            console.log('PosComponent is unmounting');
        };
    }, []);

    console.log(message); // 'Message in a bottle 🍾'

    wire.doubleIt(2)
        .then(data => {
            console.log(data); // 4
        });

    return (
        <div style={{ padding: '20px', border: '1px solid #ddd', borderRadius: '5px' }}>
            <h2>POS Component</h2>
            <p>Test message: {message}</p>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ul>
            <button onClick={() => alert('Button Clicked!')}>Test Button</button>
        </div>
    );
}

export default PosComponent;
