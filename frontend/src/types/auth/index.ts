/* eslint-disable @typescript-eslint/no-explicit-any */
export interface RolesInterface {
  id: number,
  name:string
  guard_name?:string
  created_at?:string
  updated_at?:string
}
export interface UserInterface {
  id: number,
  name: string,
  email: string,
  password?: string,
  roles?: RolesInterface[]
  permissions?: Record<string, any>[]
}

export interface LoginFormInterface {
  email: string,
  password: string,
}

export interface RegisterFormInterface {
  name: string,
  email: string,
  password: string,
  password_confirmation: string,
}