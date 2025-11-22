/* eslint-disable @typescript-eslint/no-explicit-any */
declare global {
  interface Window {
    Pusher: any
    Echo: any
  }
  const Pusher: any
  const Echo: any
}

export {}
