import { RangeValue } from "@ionic/core";

interface Availability {
    active: boolean,
    created_at: Date,
    description: string,
    id: number,
    status: string,
    updated_at: Date
}

interface Profile {
    availability: Availability;
    availability_id: number;
    created_at: Date;
    id: number;
    updated_at: Date;
    user_id: number;
    avatar: string;
    bio: string;
    birthdate: Date;
    city: string;
    country: string;
    cover_photo: string;
    interests: string;
    languages: string;
    name: string;
    relationship_status: string;
    sexual_orientation: string;
    surname: string;
}

interface User {
    created_at: Date;
    email: string;
    email_verified_at: Date;
    id: number;
    is_admin: boolean;
    name: string;
    updated_at: Date;
    profile?: Profile;
}
interface UserLocation {
  created_at: string;
  id: number;
  latitude: number;
  longitude: number;
  updated_at: string;
  user_id: number;
}

interface Ranges {
    min: number;
    max: number;
}

interface Filters {
    online: number;
    radiusKm: number;
    status: string;
    name: string;
    surname: string;
    city: string;
    country: string;
    age: RangeValue;
    bio: string;
    interests: string;
    relationship_status: string;
    sexual_orientation: string;
    languages: string;
}

interface Message {
  content: string;
  fromMe: boolean;
  timestamp: string;
}

interface Conversation {
  user: Profile;
  messages: Message[];
  newMessage: string;
}

interface ToastMessage {
  id: number
  text: string
  show: boolean
}

export type { Availability, Profile, User, UserLocation, Filters, Ranges, Conversation, Message, ToastMessage };
