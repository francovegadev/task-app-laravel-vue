export const todayDate = () => {
  const date = new Date()
  const fecha = `${date.getDay()}-${date.getMonth() + 1}-${date.getFullYear()}`
  return fecha
}
