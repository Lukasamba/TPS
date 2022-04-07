using Microsoft.EntityFrameworkCore.ChangeTracking;
using System;
using System.Collections.Generic;
using System.Linq;

namespace TPS.Extensions
{
    public static class EntityEntryExtensions
    {
        public static void MovePropertyDataBlackList(this EntityEntry target, object source, IEnumerable<string> blacklistedProprties)
        {
            MovePropertyData(target, source, (prop) => blacklistedProprties.Contains(prop.Metadata.Name));
        }

        public static void MovePropertyDataWhiteList(this EntityEntry target, object source, IEnumerable<string> whitelistedProprties)
        {
            MovePropertyData(target, source, (prop) => !whitelistedProprties.Contains(prop.Metadata.Name));
        }

        public static void MovePropertyData(this EntityEntry target, object source, Func<PropertyEntry, bool> isBlacklisted)
        {
            foreach (var prop in target.Properties)
            {
                if (isBlacklisted(prop))
                    continue;

                var propertyInfo = prop.Metadata.PropertyInfo;
                var newValue = propertyInfo.GetValue(source);
                if (!newValue.isDefault()) {
                    prop.CurrentValue = newValue;
                }
            }
        }

        private static bool isDefault(this object value)
        {
            if(value == default)
                return true;
            if (value is int || value is long)
                return (int)value == default(int);
            return false;
        }
    }
}
