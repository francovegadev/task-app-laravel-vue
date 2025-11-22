import type { TasksFormInterface } from "@/types/tasks";

export const toFormData = (payload: TasksFormInterface): FormData => {
  const fd = new FormData();

  fd.append("title", payload.title);
  fd.append("description", payload.description);
  fd.append("due_date", payload.due_date.toString());
  fd.append("status", payload.status);
  fd.append("user_id", payload.user_id.toString());

  if (payload.image) {
    fd.append("image", payload.image);
  }

  return fd;
}
