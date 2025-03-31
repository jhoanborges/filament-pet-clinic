import { Card } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Minus, Plus, Star } from "lucide-react"

interface ProductCardProps {
  image: string
  title: string
  price: number
  discount?: number
  rating?: number
  category: string
}

export function ProductCard({ image, title, price, discount, rating = 4.5, category }: ProductCardProps) {
  return (
    <Card className="overflow-hidden">
      <div className="relative">
        <img src={image || "/placeholder.svg"} alt={title} className="w-full h-40 object-cover" />
        {discount && (
          <div className="absolute top-2 left-2 bg-orange-400 text-white px-2 py-1 rounded-md text-xs font-medium">
            {discount}% Off
          </div>
        )}
      </div>
      <div className="p-3">
        <div className="text-xs text-gray-500 mb-1">{category}</div>
        <h3 className="text-sm font-medium mb-1">{title}</h3>
        <div className="flex justify-between items-center mb-2">
          <span className="text-purple font-bold">${price.toFixed(2)}</span>
          <div className="flex items-center gap-1">
            <Star className="h-3 w-3 fill-orange-400 text-orange-400" />
            <span className="text-xs text-gray-500">{rating}</span>
          </div>
        </div>
        <div className="flex items-center justify-between">
          <Button variant="outline" size="icon" className="rounded-full">
            <Minus className="h-4 w-4" />
          </Button>
          <span className="font-medium">1</span>
          <Button variant="outline" size="icon" className="rounded-full">
            <Plus className="h-4 w-4" />
          </Button>
        </div>
      </div>
    </Card>
  )
}

