export interface PaginationData {
  page: number
  limit: number
  total: number
  pages: number
}

export interface ApiResponse {
  data: Export[]
  pagination: PaginationData
}

export interface Filters {
  page: number
  limit: number
  gatewayCode: string
  status: string
  propertyId: string
}
