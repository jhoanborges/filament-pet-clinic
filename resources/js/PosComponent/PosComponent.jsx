import React, { useEffect } from 'react';
import { Header } from "./header"
import { CategoryFilter } from "./category-filter"
import { ProductGrid } from "./product-grid"
import { Cart } from "./cart"

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
        <div className="flex h-screen bg-gray-100">
        <div className="flex-1 flex flex-col overflow-hidden">
          <Header />
          <div className="flex-1 flex overflow-hidden">
            <main className="flex-1 overflow-auto p-4">
              <CategoryFilter />
              <ProductGrid />
            </main>
            <Cart />
          </div>
        </div>
      </div>
    );
}

export default PosComponent;
