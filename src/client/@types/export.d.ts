export interface Export {
  id: number
  property: { id: number
    title: string }
  gateway: { id: number
    code: string
    name: string }
  status: 'pending' | 'in_progress' | 'completed' | 'failed' // Could be an enum
  externalId: string | null
  response: Record<string, unknown> | null
  createdAt: string
  updatedAt: string
}
