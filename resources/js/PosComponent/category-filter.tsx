import { Grid, Bone, Pill, Scissors, Cat, Dog } from "lucide-react"

const categories = [
  { icon: Grid, label: "All", items: "187 Items", active: true },
  { icon: Bone, label: "Nutrition", items: "45 Items" },
  { icon: Pill, label: "Medication", items: "38 Items" },
  { icon: Scissors, label: "Grooming", items: "29 Items" },
  { icon: Dog, label: "Dog Care", items: "42 Items" },
  { icon: Cat, label: "Cat Care", items: "33 Items" },
]

export function CategoryFilter() {
  return (
    <div className="flex gap-3 mb-4 overflow-x-auto pb-2">
      {categories.map((category, index) => (
        <div
          key={index}
          className={`flex flex-col items-center p-3 rounded-xl min-w-[100px] ${
            category.active ? "bg-purple bg-opacity-10 text-purple" : "bg-white"
          } border cursor-pointer hover:bg-purple hover:bg-opacity-10 hover:text-purple`}
        >
          <category.icon className="h-6 w-6 mb-1" />
          <span className="text-sm font-medium">{category.label}</span>
          <span className="text-xs text-gray-500">{category.items}</span>
        </div>
      ))}
    </div>
  )
}

