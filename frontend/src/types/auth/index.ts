/* eslint-disable @typescript-eslint/no-explicit-any */
export interface UserInterface {
  id: number,
  name: string,
  email: string,
  password?: string,
  roles?: Record<string, any>[]
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