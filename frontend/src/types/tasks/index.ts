import type { UserInterface } from '../auth'

export interface ImageInterface extends File {
  path?: string,
  name: string
}

export interface TasksInterface {
  id: number
  title: string
  description: string
  image: null | ImageInterface
  status: 'completed' | 'in_progress' | 'pending'
  due_date: Date | string
  user?: UserInterface
  user_id: number
}

export interface TasksStatusInterface {
  completed: string
  in_progress: string
  pending: string
}

export interface TasksFormInterface {
  id?: number
  title: string
  description: string
  image: null | File
  status: 'completed' | 'in_progress' | 'pending' | ''
  due_date: Date | string
  user_id: number
}

export interface LaravelResponseCollectionInterface<T> {
  data: T[],
  links?: {
    url: string,
    label: string,
    active: boolean
  }[],
  current_page?: number,
  last_page?: number,
  per_page?: number,
  total?: number,
  from?: number,
  to?: number,
  path?: string,
  first_page_url?: string | null,
  last_page_url?: string | null,
  next_page_url?: string | null,
  prev_page_url?: string | null
}