import { ProductCard } from "./product-card"

const productItems = [
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Royal Canin Veterinary Diet",
    price: 64.99,
    discount: 10,
    category: "Nutrition",
    rating: 4.8,
  },
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Frontline Plus Flea & Tick Treatment",
    price: 42.99,
    category: "Medication",
    rating: 4.7,
  },
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Professional Pet Nail Trimmer",
    price: 24.99,
    category: "Grooming",
    rating: 4.5,
  },
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Orthopedic Dog Bed - Large",
    price: 89.99,
    category: "Dog Care",
    rating: 4.9,
  },
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Automatic Cat Water Fountain",
    price: 34.99,
    category: "Cat Care",
    rating: 4.6,
  },
  {
    image:
      "https://hebbkx1anhila5yf.public.blob.vercel-storage.com/WhatsApp%20Image%202025-01-12%20at%2012.32.42%20PM-QicgA83ZI0TfZlOynDOqlhOGnbwzEv.jpeg",
    title: "Prescription Diet Digestive Care",
    price: 52.99,
    discount: 15,
    category: "Nutrition",
    rating: 4.7,
  },
]

export function ProductGrid() {
  return (
    <div className="grid grid-cols-3 gap-4">
      {productItems.map((item, index) => (
        <ProductCard key={index} {...item} />
      ))}
    </div>
  )
}

