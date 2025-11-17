import type { UserInterface } from "../auth";

export interface TasksInterface {
  id: number,
  title: string,
  description: string,
  status: 'completed' | 'in_progress' | 'pending',
  due_date: Date | string,
  user?: UserInterface,
  user_id: number
}

export interface TasksStatusInterface {
  completed: string,
  in_progress: string,
  pending: string
}

export interface TasksFormInterface {
  id?: number,
  title: string,
  description: string,
  status: 'completed' | 'in_progress' | 'pending' | '',
  due_date: Date | string,
  user_id: number
}