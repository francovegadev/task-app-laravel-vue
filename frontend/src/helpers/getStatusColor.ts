const StatusColor = {
  completed: 'text-success',
  in_progress: 'text-warning',
  pending: 'text-danger',
}
export const getStatusColor = (status: string): string => {
  return StatusColor[status as keyof typeof StatusColor] || 'text-faded'
}